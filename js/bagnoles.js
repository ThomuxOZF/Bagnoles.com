let position = 0;
const logo = document.getElementById("log");

setInterval(() => {
    position += 5;
    logo.style.left= position + "px";

    if (position > 600) {
        position += 10;
        logo.style.right= position + "px";
    }
    if (position > window.innerWidth) {
        position =0;
        logo.style.left= position + "px";
    }
    // if (position > 600) {
    //     position = 600;
    // }
    
}, 40);