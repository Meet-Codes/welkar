"use client"

import MorphGallery from "@/components/ui/morph-gallery"

const ITEMS = [
  {
    src: "https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=1600&q=80",
    thumb: "https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=200&q=70",
    alt: "Welkar Aura White Silver Dial - 316L Surgical Steel",
  },
  {
    src: "https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80",
    thumb: "https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=200&q=70",
    alt: "Welkar Stealth Square Dial - Japanese Quartz Precision",
  },
  {
    src: "https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1600&q=80",
    thumb: "https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=200&q=70",
    alt: "Welkar Petite Milanese Mesh - Rose Gold Grace",
  },
  {
    src: "https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=1600&q=80",
    thumb: "https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=200&q=70",
    alt: "Welkar Silver Day-Date Classic - Dual Calendar Window",
  },
  {
    src: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1600&q=80",
    thumb: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=200&q=70",
    alt: "Welkar Sunray Teal Steel - Shimmering Atelier Face",
  },
]

export default function Demo() {
  return (
    <div className="relative w-full">
      <MorphGallery items={ITEMS} autoplay={4500} />
    </div>
  )
}
