const titleInputElem = document.getElementById("title");
const categoryInputElem = document.getElementById("category");
const contentInputElem = document.getElementById("content");
const submitButtonElem = document.getElementById("submitButton");

const error_titleElem = document.getElementById("error_title");
const error_categoryElem = document.getElementById("error_category");
const error_contentElem = document.getElementById("error_content");

document.addEventListener("DOMContentLoaded", handleForm);

function handleForm () {
    submitButtonElem.addEventListener("click", () => {
        const title = titleInputElem.value.trim();
        const category = categoryInputElem.value.toLowerCase();
        const content = contentInputElem.value.trim();
        let isDataValid = true;

        // clear error fields
        error_titleElem.textContent = "";
        error_contentElem.textContent = "";

        // check input validity
        let title_len = title.length;
        if (title_len < 5) {
            error_titleElem.textContent = "titre precis requis (>=5 char)";
            isDataValid = false;
        }
        else if (title_len > 80) {
            error_titleElem.textContent = "titre trop long (>80 char)";
            isDataValid = false;
        }

        let content_length = content.length;
        if (content_length < 10) {
            error_contentElem.textContent = "description precise requise (>=10 char)";
            isDataValid = false;
        }
        else if (content_length > 65535) {
            error_contentElem.textContent = "description trop longue (>65535 char)";
            isDataValid = false;
        }

        if (isDataValid) {
            console.log("Data valid");
            const data = {
                "title": title,
                "category": category,
                "content": content
            }
            postData("../../src/process_data.php", data);
        }
    })
}

function postData(url, data) {
    return $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function (response) {
            console.log(response);
        },
        error: function (error) {
            console.error("Error:", error);
        }
    });
}