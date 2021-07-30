import React from "react";
import ObjectsTabs from "../partials/ObjectsTabs";
import Box from "@material-ui/core/Box";
import ListSubheader from "@material-ui/core/ListSubheader";
import { Container } from "@material-ui/core";

function Catalogue() {
	return (
		<Box mt={15} textAlign="center">
			<Container>
				<h1>Le Catalogue des objets</h1>
				<ObjectsTabs />
			</Container>
		</Box>
	);
}

export default Catalogue;
