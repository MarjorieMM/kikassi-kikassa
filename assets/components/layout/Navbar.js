import React from "react";
import logo from "../../../public/photos/logos/FOURMI LOGO5.1. vert web 1000Px.png";
import { Link, BrowserRouter as Router } from "react-router-dom";
import Account from "../users/Account";
import Borrowings from "../users/Borrowings";

function Navbar() {
	return (
		<div>
			<nav>
				<Router>
					<Link to="/">
						<img src={logo} alt="logo-fourmi-kikassa" width="10%" />
					</Link>
					<Link to="/bibliotheque-objets/se-connecter">
						Connexion à mon compte
					</Link>
					<Link to="/bibliotheque-objets/mon-compte" component={Account}>
						Mon profil
					</Link>
					<Link to="/bibliotheque-objets/mes-emprunts" component={Borrowings}>
						Mes emprunts
					</Link>
				</Router>
			</nav>
		</div>
	);
}

export default Navbar;
