import React from 'react';
import { HeroSection } from '@/components/ui/feature-carousel';

export default function FeatureCarouselDemo() {
  const images = [
    {
      src: 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80',
      alt: 'Hydro Master 30M Timepiece',
      title: 'Hydro Master 30M',
      tag: 'Tested Submersion',
    },
    {
      src: 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80',
      alt: 'Executive Steel Chronograph',
      title: 'Executive Steel 316L',
      tag: 'Surgical Alloy',
    },
    {
      src: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80',
      alt: 'Stealth Square Monolith',
      title: 'Stealth Square Monolith',
      tag: 'Bauhaus Dial',
    },
    {
      src: 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80',
      alt: 'Rose Gold Petite Capsule',
      title: 'Rose Gold Petite',
      tag: 'Jewelry Mesh',
    },
    {
      src: 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=600&q=80',
      alt: 'Silver Day-Date Precision',
      title: 'Silver Day-Date Classic',
      tag: 'Japanese Quartz',
    },
  ];

  return (
    <div className="w-full">
      <HeroSection
        title={<>Hydro-Pressure <span className="text-primary">Precision Engineering</span></>}
        subtitle="Every Welkar timepiece is pressure-chamber tested to simulated 30M hydrostatic depth."
        images={images}
      />
    </div>
  );
}
