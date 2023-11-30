const form = document.querySelector(".login-signup .form #loginForm");
const err_txt = document.querySelector(".login-signup .form .err-txt");

form.onsubmit = (e) => {
    e.preventDefault();
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/login.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              let data = xhr.response;
              if(data === "success"){
                location.href = "user.php";
              }else{
                showErr(data);
              }
          }
      }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}