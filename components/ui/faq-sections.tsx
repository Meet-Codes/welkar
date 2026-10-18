"use client";

import { cn } from "@/lib/utils";
import * as React from "react";

export interface FAQItem {
  question: string;
  answer: string;
}

export interface FAQSectionProps extends React.HTMLAttributes<HTMLDivElement> {
  badgeText?: string;
  title?: string;
  description?: string;
  imageUrl?: string;
  faqs?: FAQItem[];
}

const defaultFaqs: FAQItem[] = [
  {
    question: "How does Pan-India Cash on Delivery (COD) and verification work?",
    answer: "We provide insured Express COD across all Indian pin codes. You can inspect the tamper-evident holographic seal and package integrity before completing cash or UPI payment.",
  },
  {
    question: "Are all timepieces covered under authentic atelier warranty?",
    answer: "Yes, every Welkar timepiece includes an official 1-Year mechanism and seal warranty with unique stamped serial cards registered in our atelier database.",
  },
  {
    question: "What hydrostatic depth and water resistance standards are tested?",
    answer: "Every watch undergoes hydrostatic chamber testing to minimum 30M (3 ATM) submersion standards, making it impervious to everyday rain, handwashing, and splashes.",
  },
  {
    question: "How can I request strap sizing adjustments or concierge assistance?",
    answer: "Our dedicated WhatsApp Concierge desk provides instant complimentary link-removal guidance, warranty activation, and personal styling support.",
  },
  {
    question: "What is your return and doorstep exchange policy?",
    answer: "We offer an unconditional 7-day doorstep replacement and exchange window if your timepiece arrives damaged or fails to exceed your expectations.",
  },
];

export function FAQSections({
  className,
  badgeText = "FAQ's",
  title = "Looking for answers?",
  description = "Everything you need to know about serial authentication, insured transit, hydrostatic seals, and atelier care.",
  imageUrl = "https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80",
  faqs = defaultFaqs,
  ...props
}: FAQSectionProps) {
  const [openIndex, setOpenIndex] = React.useState<number | null>(0);

  return (
    <section className={cn("py-12 bg-surface-canvas border-b border-border/50", className)} {...props}>
      <div className="max-w-5xl mx-auto flex flex-col md:flex-row items-start justify-center gap-10 px-4 md:px-6">
        <div className="w-full md:w-5/12 sticky top-24">
          <img
            className="w-full rounded-2xl shadow-lg object-cover aspect-[4/5] border border-border/50"
            src={imageUrl}
            alt="Welkar Horology FAQ"
            loading="lazy"
          />
        </div>
        <div className="w-full md:w-7/12">
          <p className="text-primary text-xs font-bold uppercase tracking-wider mb-1">{badgeText}</p>
          <h2 className="text-3xl font-bold tracking-tight text-foreground font-serif">{title}</h2>
          <p className="text-sm text-muted-foreground mt-2 pb-6 border-b border-border/40">
            {description}
          </p>
          <div className="divide-y divide-border/60">
            {faqs.map((faq, index) => {
              const isOpen = openIndex === index;
              return (
                <div
                  className="py-4 cursor-pointer group transition-colors"
                  key={index}
                  onClick={() => setOpenIndex(isOpen ? null : index)}
                >
                  <div className="flex items-center justify-between gap-4">
                    <h3 className="text-base font-medium text-foreground group-hover:text-primary transition-colors">
                      {faq.question}
                    </h3>
                    <svg
                      width="18"
                      height="18"
                      viewBox="0 0 18 18"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                      className={cn(
                        "transition-transform duration-300 ease-in-out shrink-0 text-muted-foreground",
                        isOpen && "rotate-180 text-primary"
                      )}
                    >
                      <path
                        d="m4.5 7.2 3.793 3.793a1 1 0 0 0 1.414 0L13.5 7.2"
                        stroke="currentColor"
                        strokeWidth="1.75"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                      />
                    </svg>
                  </div>
                  <div
                    className={cn(
                      "grid transition-all duration-300 ease-in-out",
                      isOpen ? "grid-rows-[1fr] opacity-100 pt-3" : "grid-rows-[0fr] opacity-0"
                    )}
                  >
                    <div className="overflow-hidden">
                      <p className="text-sm text-muted-foreground leading-relaxed pr-6">
                        {faq.answer}
                      </p>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </div>
    </section>
  );
}

export default FAQSections;
