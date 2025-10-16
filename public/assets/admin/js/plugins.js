const assetsPath = document.querySelector("meta[name='asset-url']")?.getAttribute("content") || "assets/";

const elements = [
  ...document.querySelectorAll("[toast-list]"),
  ...document.querySelectorAll("[data-choices]"),
  ...document.querySelectorAll("[data-provider]")
];

if (elements.length > 0) {
  const scripts = [
    "https://cdn.jsdelivr.net/npm/toastify-js",
    `${assetsPath}libs/choices.js/public/assets/scripts/choices.min.js`,
    `${assetsPath}libs/flatpickr/flatpickr.min.js`
  ];

  scripts.forEach(src => {
    const script = document.createElement("script");
    script.type = "text/javascript";
    script.src = src;
    document.head.appendChild(script);
  });
}