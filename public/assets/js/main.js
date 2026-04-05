(() => {
  function initCarousel(root) {
    const grid = root.querySelector(".news-grid");
    const prev = root.querySelector('[data-carousel="prev"]');
    const next = root.querySelector('[data-carousel="next"]');
    if (!grid || !prev || !next) return;

    function getStep() {
      const firstCard = grid.querySelector(".news-card");
      if (!firstCard) return 340;
      const cardRect = firstCard.getBoundingClientRect();
      const styles = window.getComputedStyle(grid);
      const gap = parseFloat(styles.columnGap || styles.gap || "0") || 0;
      return Math.max(200, Math.round(cardRect.width + gap));
    }

    function updateDisabled() {
      const maxScrollLeft = grid.scrollWidth - grid.clientWidth;
      prev.disabled = grid.scrollLeft <= 1;
      next.disabled = grid.scrollLeft >= maxScrollLeft - 1;
    }

    function refresh() {
      const hasOverflow = grid.scrollWidth > grid.clientWidth + 2;
      root.classList.toggle("is-carousel", hasOverflow);
      if (!hasOverflow) {
        prev.disabled = true;
        next.disabled = true;
        return;
      }
      updateDisabled();
    }

    // Requested UX:
    // - left arrow: reveal the hidden (next) item
    // - right arrow: go back to the initial items
    prev.addEventListener("click", () => {
      grid.scrollBy({ left: -getStep(), behavior: "smooth" });
    });

    next.addEventListener("click", () => {
      grid.scrollBy({ left: getStep(), behavior: "smooth" });
    });

    grid.addEventListener("scroll", () => {
      window.requestAnimationFrame(updateDisabled);
    });

    window.addEventListener("resize", refresh);
    window.addEventListener("load", refresh);
    refresh();
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".news-carousel").forEach(initCarousel);
  });
})();

