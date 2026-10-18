import { InteractiveProductCard } from "@/components/ui/card-7";

export default function InteractiveProductCardDemo() {
  return (
    <div className="flex min-h-screen w-full flex-col items-center justify-center gap-6 bg-background p-4">
      <InteractiveProductCard
        title="Welkar Hydro Master"
        description="Hydrostatic Submersion 30M"
        price="₹2,499"
        imageUrl="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80"
        logoUrl="https://cdn.21st.dev/assets/mirror/54/54bb728904e14583753fcde786a9901b53ca9c8401ac207fb516841f9863c55a.svg"
      />
    </div>
  );
}
