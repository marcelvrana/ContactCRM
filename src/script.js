//JS
import bootstrap from 'bootstrap';
import Popper from "popper.js";
import naja from 'naja';
import netteForms from 'nette-forms';
// import 'js-datepicker/dist/datepicker.min';
import datepicker from 'js-datepicker'

//SCSS
import 'js-datepicker/src/datepicker.scss';
import 'bootstrap/scss/bootstrap.scss';
import './scss/style.scss';

var $ = require("jquery");
global.jQuery = global.$ = require('jquery');
document.addEventListener('DOMContentLoaded', () => naja.initialize());

window.Nette = netteForms;
netteForms.initOnLoad();

const picker = datepicker('[data-param="date"]',{
    startDay: 1,
    formatter: (input, date, instance) => {
        input.value = date.toLocaleDateString() // => '1/1/2099'
    }
});