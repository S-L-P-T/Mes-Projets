$(".arrow").click(function(){
    if (document.querySelector('.network').style.display == "none") {
        document.querySelector('.network').style.display = "flex";
        document.querySelector('.arr').style.bottom = "70px";
    } else {
        document.querySelector('.network').style.display = "none";
        document.querySelector('.arr').style.bottom = "0";
    }
})

document.querySelector('.option').style.display = "none";
document.querySelector('.network').style.display = "none";

$(".setting").click(function(){
    $(".option").toggle('slow');
})
