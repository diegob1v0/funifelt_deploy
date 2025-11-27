I have updated the `src/scss/market/_apps-header.scss` file to make the search bar longer and centered when active.

Specifically, I've made the following changes within the `.apps-header.search-active` block:
- Increased the `width` of `.apps-header__search-container` to `80%`.
- Added `justify-content: center;` to `.apps-header__content` to center the search bar.

**Please ensure your Gulp compilation process is run to compile these SCSS changes into your `public/build/css/app.css` file for them to take effect.**