// Lightweight scroll reveal using Intersection Observer.
// Avoids importing full GSAP to keep bundle small.
// For GSAP-heavy work, dynamically import gsap + ScrollTrigger.

export function useScrollReveal() {
  let observer: IntersectionObserver | null = null

  function init(selector: string = '[data-reveal]') {
    if (typeof window === 'undefined') return

    observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-revealed')
            observer?.unobserve(entry.target)
          }
        })
      },
      { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
    )

    document.querySelectorAll(selector).forEach((el) => {
      observer?.observe(el)
    })
  }

  function destroy() {
    observer?.disconnect()
    observer = null
  }

  return { init, destroy }
}
