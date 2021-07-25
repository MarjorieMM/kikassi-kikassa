import React from "react";
import ReactDOM from "react-dom";
import { BrowserRouter, Route } from "react-router-dom";
import Catalogue from "./pages/Catalogue";
import Layout from "./layout/Layout";
import Homepage from "./pages/Homepage";
import Borrowings from "./users/Borrowings";
import Account from "./users/Account";

ReactDOM.render(
	<BrowserRouter>
		<React.StrictMode>
			<Layout>
				<Route exact path="/" component={Homepage} />
				<Route exact path="/bibliotheque-objets" component={Catalogue} />
				<Route
					exact
					path="/bibliotheque-objets/mon-compte/mon-historique"
					component={Borrowings}
				/>
				<Route
					exact
					path="/bibliotheque-objets/mon-compte"
					component={Account}
				/>
			</Layout>
		</React.StrictMode>
	</BrowserRouter>,
	document.getElementById("root")
);
