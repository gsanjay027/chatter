const form = document.querySelector(".login-signup .form #signupForm");
const err_txt = document.querySelector(".login-signup .form .err-txt");

form.onsubmit = (e) => {
    e.preventDefault();
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/signup.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              let data = xhr.response;
              if(data === "success"){
                location.href = "index.php";
              }else{
                showErr(data);
              }
          }
      }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}