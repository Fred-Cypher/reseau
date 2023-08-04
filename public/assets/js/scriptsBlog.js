window.onload = () => {
    let visibility = document.querySelectorAll("[type=checkbox]")

    for (let visibilityButton of visibility) {
        visibilityButton.addEventListener("click", function () {
            let xmlhttp = new XMLHttpRequest;

            xmlhttp.open("post", 'blog/index.html.twig')
            xmlhttp.send()
        })
    }

}