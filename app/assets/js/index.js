const button1 = document.querySelector('#button1') 
const modal = document.querySelector('.modal') 
const close = document.querySelector('.modal__content--close') 

button1.addEventListener('click', () =>{
    modal.classList.remove('hidden')
    modal.classList.add('visible')
})

close.addEventListener('click', () =>{
    modal.classList.add('hidden')
    modal.classList.remove('visible')
})