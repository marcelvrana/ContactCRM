//JS
import bootstrap from 'bootstrap';
import Popper from "popper.js";
import naja from 'naja';
import netteForms from 'nette-forms';
import Sortable from 'sortablejs';

//SCSS
import 'bootstrap/scss/bootstrap.scss';
import './scss/style.scss';

var $ = require("jquery");
global.jQuery = global.$ = require('jquery');
document.addEventListener('DOMContentLoaded', () => naja.initialize());

window.Nette = netteForms;
netteForms.initOnLoad();