const chat_place = document.querySelector(".message-box .chat-list");
const status_place = document.querySelector(".message-box .info .profile .status");
const form = document.querySelector(".message-box #sendMsg");
const sendMsg_btn = document.querySelector(".message-box #sendMsg .submit");
const sendMsg_inp = document.querySelector(".message-box #sendMsg .message");

const image = document.querySelector(".message-box .images");
var scrollingDiv = document.getElementById('chat-lister');

image.onchange = () => {
  uploadImage();
}

scrollingDiv.scrollTop = 0;

var scrollSpeed = 200000;
var scrollDirection = 1;

function autoScroll() {
  scrollingDiv.scrollTop += scrollSpeed * scrollDirection;
}


setInterval( () => {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/getChat.php", true);
  xhr.setRequestHeader("content-type", "application/x-www-form-urlencoded");
  xhr.onload = ()=>{
    if(xhr.readyState === XMLHttpRequest.DONE){
        if(xhr.status === 200){
            let data = JSON.parse(xhr.response);
            chat_place.innerHTML = data["data"];
            status_place.innerHTML = data["status"];
            if (data["status"] === "Offline")
              status_place.classList.replace("Online", "Offline");
            else if (data["status"] === "Online")
              status_place.classList.replace("Offline", "Online");
        }
    }
  }
  xhr.send("incommingid="+incomming_id);
}, 500);

form.onsubmit = (e) => {
  e.preventDefault();
  if (sendMsg_btn.classList.contains("active")) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/insertChat.php", true);
    xhr.onload = () => {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          let data = xhr.response;
          if (data === "success") {
            // location.href = "user.php";
          } else {
            alert(data);
          }
        }
      }
    }
    let formData = new FormData(form);
    formData.append("incommingid", incomming_id);
    xhr.send(formData);
  }
  sendMsg_inp.value = "";
}

sendMsg_inp.onkeyup = () => {
  if (sendMsg_inp.value !== "")
    sendMsg_btn.classList.add("active");
  else
    sendMsg_btn.classList.remove("active");
}

function uploadImage() {
    var fileInput = document.getElementById('fileInput');
    var imageContainer = document.getElementById('imageContainer');
    var uploadedImage = document.getElementById('uploadedImage');

    var file = fileInput.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var base64Image = e.target.result;

            storeImageInDatabase(base64Image);
            
            uploadedImage.src = base64Image;
            imageContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function storeImageInDatabase(base64Image) {
    // Use AJAX to send the base64Image to a PHP script for database storage
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'storeImage.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
        }
    };
    xhr.send('image=' + encodeURIComponent(base64Image));
}

function viewImage() {
    // Use AJAX to retrieve the base64Image from the database
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'getImage.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var base64Image = xhr.responseText;
            var uploadedImage = document.getElementById('uploadedImage');
            var imageContainer = document.getElementById('imageContainer');
            
            // Display the retrieved image
            uploadedImage.src = base64Image;
            imageContainer.style.display = 'block';
        }
    };
    xhr.send();
}