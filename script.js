const sortBtns = document.querySelectorAll(".job-id > *");
const sortItems = document.querySelectorAll(".jobs-container .jList");

sortBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
        sortBtns.forEach((btn) => btn.classList.remove("active"));
        btn.classList.add("active");

        const targetData = btn.getAttribute('data-target');

        sortItems.forEach((item) => {
            if (targetData === "all" || item.getAttribute("data-item") === targetData) {
                item.classList.remove("delete");
            } else {
                item.classList.add("delete");
            }
        });
    });
});
