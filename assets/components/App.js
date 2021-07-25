import React from "react";
import ReactDOM from "react-dom";
import { HashRouter, BrowserRouter, Route, Switch } from "react-router-dom";
import Catalogue from "./pages/Catalogue";
import DetailsObject from "./pages/DetailsObject";
import Order from "./pages/Order";
import Layout from "./layout/Layout";
import Homepage from "./pages/Homepage";
import Borrowings from "./users/Borrowings";
import Account from "./users/Account";

function App() {
	return (
		<div>
			<Switch>
				<Route
					path="/bibliotheque-objets/mon-compte/mon-historique"
					component={Borrowings}
				/>
				<Route path="/bibliotheque-objets/mon-compte" component={Account} />

				<Route
					path="/bibliotheque-objets/objet/:id/creation-emprunt"
					component={Order}
				/>
				<Route
					path="/bibliotheque-objets/objet/:id"
					component={DetailsObject}
				/>
				<Route path="/bibliotheque-objets" component={Catalogue} />
				<Route exact path="/" component={Homepage} />
			</Switch>
		</div>
	);
}

ReactDOM.render(
	<React.StrictMode>
		<BrowserRouter>
			<Layout>
				<App />
			</Layout>
		</BrowserRouter>
	</React.StrictMode>,
	document.getElementById("root")
);
