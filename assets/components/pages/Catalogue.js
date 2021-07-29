import React from "react";
import { ObjectsList } from "../partials/ObjectsList";
import Box from "@material-ui/core/Box";
import ListSubheader from "@material-ui/core/ListSubheader";
import { Container } from "@material-ui/core";

function Catalogue() {
	return (
		<Box mt={15}>
			<Container>
				<h1>Le Catalogue</h1>
				<ObjectsList />
			</Container>
		</Box>
	);
}

export default Catalogue;
