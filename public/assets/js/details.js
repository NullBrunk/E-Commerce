function menu(id){const menu=document.getElementById(id);menu.classList.toggle("none")}function showcomm(){const form=document.getElementById("formcomm"),chevron=document.getElementById("chevron"),span=document.getElementById("commcontent");form.classList.toggle("none"),chevron.classList.toggle("bx-chevron-right"),chevron.classList.toggle("bx-chevron-down"),"Click here to close this menu"===span.innerText?span.innerText="Click here to post a comment ":span.innerText="Click here to close this menu"}async function haveiliked(url,id){let resp=await fetch(url),data;1==(await resp.json()).value?document.getElementById(id).classList.add("bi-heart-fill"):document.getElementById(id).classList.add("bi-heart")}async function heartclick(url,id,num){let resp=await fetch(url),elem=document.getElementById(id);elem.classList.toggle("bi-heart-fill"),elem.classList.toggle("bi-heart");let number=document.getElementById(num);return elem.classList.contains("bi-heart-fill")?(number.innerText=parseInt(number.innerText)+1,!0):(number.innerText=parseInt(number.innerText)-1,!1)}function addtocart(id){return fetch("/cart/add/"+id).then(resp=>{resp.redirected||resp.json().then(data=>{const id_cart_elem=data.id,url=location.protocol+"//"+window.location.hostname+":8000/api/products/"+id;fetch(url).then(response=>{response.ok&&response.json().then(data=>{let cart=document.getElementById("cart_to_fill");console.log(cart),cart.innerHTML+=`\n                                <li id="cart_${id_cart_elem}">\n                                    <p class="show_cart">\n\n                                        <img src="/storage/product_img/${data.img}"       style="padding-left: 3%; width: 22%; display: block; user-select: none !important;">\n\n                                        <a href="/details/${data.id}" style="display: block;overflow: hidden; width: 57%; margin:auto;">${data.name}</a>\n                                        <img src="/assets/img/trash.png" onclick='deleteitem("cart_${id_cart_elem}")' class="trash-cart">\n                                    </p>\n                                </li>\n                                <hr id="hrcart_${id_cart_elem}">\n                            `})});const num=document.getElementById("number");""===num.innerHTML?num.innerHTML=1:num.innerHTML=parseInt(num.innerHTML)+1})}),!0}$((function(){$("#commentTextBar").markItUp(mySettings)}));