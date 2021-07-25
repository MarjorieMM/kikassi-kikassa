import React from "react";
import { Link, BrowserRouter } from "react-router-dom";
import logo from "../../../public/photos/logos/FOURMI LOGO5.1. vert web 1000Px.png";

function Navbar() {
	return (
			<nav>
				<Link to="/">
					<img src={logo} alt="logo-fourmi-kikassa" width="10%" />
				</Link>
				{/* <Link to="/bibliotheque-objets/se-connecter">
						Connexion à mon compte
					</Link> */}
				<Link to="/bibliotheque-objets/mon-compte">Mon profil</Link>
				<Link to="/bibliotheque-objets/mon-compte/mon-historique">
					Mes emprunts
				</Link>
				<Link to="/bibliotheque-objets">Les objets</Link>
			</nav>
	);
}

export default Navbar;
