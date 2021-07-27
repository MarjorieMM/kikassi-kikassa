import React from "react";
import { ObjectsList } from "../partials/ObjectsList";
import Box from "@material-ui/core/Box";
import ListSubheader from "@material-ui/core/ListSubheader";

function Catalogue() {
	return (
		<Box mt={12}>
			<h1>Le Catalogue</h1>
			<ObjectsList />
		</Box>
	);
}

export default Catalogue;
