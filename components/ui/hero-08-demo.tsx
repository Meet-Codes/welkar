import { Hero08, type Hero08Props } from '@/components/ui/hero-08'

const values = {
  title: 'Curated Horology for Every Wrist',
  description:
    'Hand-selected masterworks engineered for bold presence and delicate elegance.',
  socialProof: 'Join 40,000+ Horology Collectors Across India',
  avatars: [
    {
      src: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&h=120&q=80',
      fallback: 'VS',
    },
    {
      src: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&h=120&q=80',
      fallback: 'AM',
    },
    {
      src: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=120&h=120&q=80',
      fallback: 'PN',
    },
  ],
  cards: [
    {
      title: 'Watches for Him',
      subtitle: 'Precision chronographs, automatic skeletons & bold silhouettes',
      image:
        'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1200&q=80',
      imageAlt: "Men's luxury wrist watch",
      invert: true,
      cta: {
        ctaEnabled: true,
        text: 'Explore For Him',
        link: '/catalog?gender=Men',
        size: 'default',
      },
    },
    {
      title: 'Watches for Her',
      subtitle: 'Petite diamond dials, rose gold finishes & jewel bracelets',
      image:
        'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1200&q=80',
      imageAlt: "Women's luxury wrist watch",
      invert: true,
      cta: {
        ctaEnabled: true,
        text: 'Explore For Her',
        link: '/catalog?gender=Women',
        size: 'default',
      },
    },
  ],
  animation: 'subtle',
} satisfies Hero08Props

export default function Hero08Demo() {
  return <Hero08 {...values} />
}
