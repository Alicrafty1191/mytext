
const updatetime = document.getElementById('updatetime');
let time = parseInt(updatetime.innerText)*1000;

let date = new Date(time);

updatetime.innerText = "Last Update: "+date;