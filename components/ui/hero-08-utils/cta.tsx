import React from 'react'
import { Button } from '@/components/ui/button'
import { cn } from '@/lib/utils'

export interface CtaProps {
  ctaEnabled?: boolean
  text: string
  link?: string
  size?: 'default' | 'sm' | 'lg' | 'icon'
}

export function Cta({
  cta,
  invert,
}: {
  cta: CtaProps
  invert?: boolean
}) {
  if (!cta.ctaEnabled) return null

  const content = (
    <Button
      size={cta.size ?? 'default'}
      className={cn(
        'font-medium transition-transform active:scale-95',
        invert
          ? 'bg-white text-black hover:bg-white/90 shadow-sm'
          : 'bg-primary text-primary-foreground hover:bg-primary/90'
      )}
    >
      {cta.text}
    </Button>
  )

  if (cta.link) {
    return <a href={cta.link} className="inline-block text-decoration-none">{content}</a>
  }

  return content
}
