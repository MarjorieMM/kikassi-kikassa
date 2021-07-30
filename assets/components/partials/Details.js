import React from "react";
import Box from "@material-ui/core/Box";
import useFetch from "../utils/useFetch";
import Carousel from "../utils/Carousel";

function Details({ photos, name }) {
	return (
		<Box mt={10}>
			<Carousel infiniteLoop={true}>
				{photos.map((photo) => (
					<img key={photo.id} src={photo.lien} />
				))}
			</Carousel>
			<h1>Détails de l'objet {name}</h1>
		</Box>
	);
}

export default Details;
