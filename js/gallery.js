const current = document.querySelector('#current');
const imgs = document.querySelectorAll('.imgs img');
const opacity = 0.5;

//Set first image opacity
imgs[0].style.opacity = opacity;

imgs.forEach(img => img.addEventListener('click', imgClick));

function imgClick(e) {
  // Reset opacity
  imgs.forEach(img => (img.style.opacity = 1));

  // Change current image to src of clicked image
  var prev = e.target.src;
  var full = prev.replace('preview', 'full');
  current.src = full;

  //Add fade0in class
  current.classList.add('fade-in');

  //Remove fade-in class after .5 sec
  setTimeout(() => current.classList.remove('fade-in'), 500)

  //Change the opacity to opacity variable
  e.target.style.opacity = opacity;
}
