import * as React from 'react';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';

// --- TYPES ---
export interface CarouselImage {
  src: string;
  alt: string;
  title?: string;
  tag?: string;
}

export interface HeroProps extends React.HTMLAttributes<HTMLDivElement> {
  title?: React.ReactNode;
  subtitle?: string;
  images: CarouselImage[];
  autoPlayInterval?: number;
}

// --- HERO SECTION / FEATURE CAROUSEL COMPONENT ---
export const HeroSection = React.forwardRef<HTMLDivElement, HeroProps>(
  ({ title, subtitle, images, autoPlayInterval = 4000, className, ...props }, ref) => {
    const [currentIndex, setCurrentIndex] = React.useState(Math.floor(images.length / 2));

    const handleNext = React.useCallback(() => {
      setCurrentIndex((prevIndex) => (prevIndex + 1) % images.length);
    }, [images.length]);

    const handlePrev = () => {
      setCurrentIndex((prevIndex) => (prevIndex - 1 + images.length) % images.length);
    };

    React.useEffect(() => {
      if (!autoPlayInterval) return;
      const timer = setInterval(() => {
        handleNext();
      }, autoPlayInterval);
      return () => clearInterval(timer);
    }, [handleNext, autoPlayInterval]);

    return (
      <div
        ref={ref}
        className={cn(
          'relative w-full flex flex-col items-center justify-center overflow-x-hidden bg-background text-foreground p-4',
          className
        )}
        {...props}
      >
        {/* Background Gradient */}
        <div className="absolute inset-0 z-0 opacity-20 pointer-events-none" aria-hidden="true">
          <div className="absolute bottom-0 left-[-20%] right-0 top-[-10%] h-[500px] w-[500px] rounded-full bg-[radial-gradient(circle_farthest-side,rgba(128,90,213,0.3),rgba(255,255,255,0))]"></div>
          <div className="absolute bottom-0 right-[-20%] top-[-10%] h-[500px] w-[500px] rounded-full bg-[radial-gradient(circle_farthest-side,rgba(0,123,255,0.3),rgba(255,255,255,0))]"></div>
        </div>

        {/* Content */}
        <div className="z-10 flex w-full flex-col items-center text-center space-y-8 md:space-y-12">
          {/* Header Section */}
          {title && (
            <div className="space-y-4">
              <h2 className="text-4xl sm:text-5xl font-bold tracking-tighter max-w-4xl">
                {title}
              </h2>
              {subtitle && (
                <p className="max-w-2xl mx-auto text-muted-foreground md:text-lg">
                  {subtitle}
                </p>
              )}
            </div>
          )}

          {/* Main Showcase Section */}
          <div className="relative w-full h-[360px] md:h-[460px] flex items-center justify-center">
            {/* Carousel Wrapper */}
            <div className="relative w-full h-full flex items-center justify-center [perspective:1000px]">
              {images.map((image, index) => {
                const offset = index - currentIndex;
                const total = images.length;
                let pos = (offset + total) % total;
                if (pos > Math.floor(total / 2)) {
                  pos = pos - total;
                }

                const isCenter = pos === 0;
                const isAdjacent = Math.abs(pos) === 1;

                return (
                  <div
                    key={index}
                    onClick={() => setCurrentIndex(index)}
                    className={cn(
                      'absolute w-52 h-[320px] md:w-64 md:h-[420px] transition-all duration-500 ease-in-out cursor-pointer',
                      'flex items-center justify-center'
                    )}
                    style={{
                      transform: `
                        translateX(${pos * 48}%) 
                        scale(${isCenter ? 1 : isAdjacent ? 0.85 : 0.7})
                        rotateY(${pos * -12}deg)
                      `,
                      zIndex: isCenter ? 10 : isAdjacent ? 5 : 1,
                      opacity: isCenter ? 1 : isAdjacent ? 0.45 : 0,
                      filter: isCenter ? 'blur(0px)' : 'blur(4px)',
                      visibility: Math.abs(pos) > 1 ? 'hidden' : 'visible',
                    }}
                  >
                    <div className="relative w-full h-full rounded-3xl overflow-hidden border-2 border-foreground/10 shadow-2xl bg-card">
                      <img
                        src={image.src}
                        alt={image.alt}
                        className="object-cover w-full h-full"
                      />
                      {image.title && (
                        <div className="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 via-black/30 to-transparent text-white text-left">
                          {image.tag && (
                            <span className="text-[10px] uppercase font-bold tracking-wider text-primary block mb-1">
                              {image.tag}
                            </span>
                          )}
                          <h4 className="text-sm font-bold tracking-tight">{image.title}</h4>
                        </div>
                      )}
                    </div>
                  </div>
                );
              })}
            </div>

            {/* Navigation Buttons */}
            <Button
              variant="outline"
              size="icon"
              className="absolute left-2 sm:left-6 top-1/2 -translate-y-1/2 rounded-full h-11 w-11 z-20 bg-background/60 backdrop-blur-md shadow-lg"
              onClick={handlePrev}
              aria-label="Previous image"
            >
              <ChevronLeft className="h-5 w-5" />
            </Button>
            <Button
              variant="outline"
              size="icon"
              className="absolute right-2 sm:right-6 top-1/2 -translate-y-1/2 rounded-full h-11 w-11 z-20 bg-background/60 backdrop-blur-md shadow-lg"
              onClick={handleNext}
              aria-label="Next image"
            >
              <ChevronRight className="h-5 w-5" />
            </Button>
          </div>
        </div>
      </div>
    );
  }
);

HeroSection.displayName = 'HeroSection';

export default HeroSection;
