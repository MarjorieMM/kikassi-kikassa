import React from "react";
import Box from "@material-ui/core/Box";
import useFetch from "../utils/useFetch";
import Carousel from "../utils/Carousel";

function Details({ photos, name, slug }) {
	return (
		<Box mt={10}>
			<Carousel infiniteLoop={true} show={2}>
				{photos.map((photo) => (
					<Box key={photo.id}>
						<img src={`/photos/${photo.lien}`} alt="photo" />
					</Box>
				))}
			</Carousel>
			<h1>Détails de l'objet {name}</h1>
		</Box>
	);
}

export default Details;
