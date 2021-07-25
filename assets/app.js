/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

require("@fortawesome/fontawesome-free/css/all.min.css");
require("@fortawesome/fontawesome-free/js/all.js");

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.sass";
import "./styles/test.sass";
import "./styles/admin.sass";
import "./styles/switch.sass";

// start the Stimulus application

import "./bootstrap";

import React from "react";
import ReactDOM from "react-dom";
import { BrowserRouter, Route, Redirect } from "react-router-dom";
import App from "./components/App";
import Catalogue from "./components/pages/Catalogue";
import Layout from "./components/layout/Layout";
import Homepage from "./components/pages/Homepage";

ReactDOM.render(
	<BrowserRouter>
		<React.StrictMode>
			<Layout>
				<Route exact path="/" component={Homepage} />

				<Route exact path="/bibliotheque-objets" component={Catalogue} />
			</Layout>
		</React.StrictMode>
	</BrowserRouter>,
	document.getElementById("root")
);
