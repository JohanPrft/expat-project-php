import {changeArticles} from "./articles.js";

document.addEventListener("DOMContentLoaded",  () => {
    const selectElem = document.getElementById("category");

    // if wrong query param redirect to full page
    if (!setSelectToQueryParam()) {
        return ;
    }
    // initialization
    changeArticles(selectElem.value);
    changePageOnSelectChange(selectElem);
});

// initialize the select button
function setSelectToQueryParam () {
    const queryParams = new URLSearchParams(window.location.search);
    const param = queryParams.get('param');
    console.log(param)

    if (param === 'emploi') {
        document.getElementById('emploi').setAttribute("selected", "")
    }
    else if (param === 'immobilier') {
        document.getElementById('immobilier').setAttribute("selected", "")
    }
    else {
        console.error(`Wrong query param ${param}`)
        return false;
    }
    return true;
}

// set a new href with selected value to update page content
function changePageOnSelectChange(selectElem) {
    selectElem.addEventListener("change", () => {
        const categoryInputElem = document.getElementById("category");
        const category = categoryInputElem.value.toLowerCase();

        window.location.href = 'articles_filter.html?param=' + category;
    });
}