import React from "react";
import Featured from "../partials/Featured";
import Connexion from "../partials/Connexion";
import Box from "@material-ui/core/Box";
import Grid from "@material-ui/core/Grid";
import ListSubheader from "@material-ui/core/ListSubheader";

export default function Homepage() {
	return (
		<Box mt={15}>
			<h1>Bienvenue</h1>
			<ListSubheader>Subheader</ListSubheader>
			<Connexion />
			<Featured />
		</Box>
	);
}
