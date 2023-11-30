const contact_place = document.querySelector(".user .contact");
const logout_btn = document.querySelector(".user .info .logout");
const search_btn = document.querySelector(".user .search .search-btn");
const search_inp = document.querySelector(".user .search .input");
const search_logo = document.querySelector(".user .search .search-btn .fas");

setInterval(() => {
    if (!search_inp.classList.contains("active")) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "php/contact.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.response;
                    contact_place.innerHTML = data;
                }
            }
        }
        xhr.send();
    }
}, 500);

search_btn.onclick = () => {
    search_inp.classList.toggle("active");
    if (search_logo.classList.contains("fa-search"))
        search_logo.classList.replace("fa-search", "fa-x");
    else if (search_logo.classList.contains("fa-x")) {
        search_inp.value = "";
        search_logo.classList.replace("fa-x", "fa-search");
    }
}

search_inp.onkeyup = () => {
    if (search_inp.value !== "") {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "php/contact.php", true);
        xhr.setRequestHeader("content-type", "application/x-www-form-urlencoded");
        xhr.onload = ()=>{
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                contact_place.innerHTML = data;
            }
        }
        }
        xhr.send("nameLike=" + search_inp.value);
    }
}

logout_btn.onclick = () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/logout.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              let data = xhr.response;
              if(data === "success"){
                location.href = "../chatter/";
              } else {
                  alert(data);
              }
          }
      }
    }
    xhr.send("logout=yes");
}