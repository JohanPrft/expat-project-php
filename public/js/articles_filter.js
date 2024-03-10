import {changeArticles} from "./articles.js";
document.addEventListener("DOMContentLoaded", () => {
    // drop button containing emploi or immobilier
    const select = document.getElementById("category");
    // article initialization needed because filter
    changeArticles(select.value);
    select.addEventListener("change", () => {
        changeArticles(select.value);
    });
});
