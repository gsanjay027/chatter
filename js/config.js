function showErr(msg) {
    err_txt.innerHTML = msg;
    err_txt.classList.add("show");
}

function hideErr() {
    err_txt.innerHTML = "";
    err_txt.classList.remove("show");
}

err_txt.onclick = () => {
    hideErr();
}