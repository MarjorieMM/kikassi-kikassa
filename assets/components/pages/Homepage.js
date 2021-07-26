import React from "react";
import Featured from "../partials/Featured";
import Connexion from "../partials/Connexion";
import Box from "@material-ui/core/Box";
import ListSubheader from "@material-ui/core/ListSubheader";

export default function Homepage() {
	return (
		<Box mt={10}>
			<ListSubheader>Subheader</ListSubheader>
			<Connexion />
			<Featured />
		</Box>
	);
}
