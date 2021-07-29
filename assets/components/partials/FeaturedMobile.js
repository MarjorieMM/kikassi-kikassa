import React from "react";
import useFetch from "../utils/useFetch";
import ObjectCard from "./ObjectCard";
import Grid from "@material-ui/core/Grid";
import { Typography } from "@material-ui/core";

export default function FeaturedMobile() {
	const { data: objects, error, isLoaded } = useFetch("/api/objets");

	const vitrine = [];
	objects.map((obj) => obj.vitrine && vitrine.push(obj));
	const random = vitrine.sort(() => 0.5 - Math.random());
	const selection = random.slice(0, 5);

	if (error) {
		return <div>Erreur: {error.message}</div>;
	} else if (!isLoaded) {
		return <div>Chargement...</div>;
	} else {
		return (
			<Grid container>
				<Typography variant="h5">Quelques objets...</Typography>
				{selection.map((object) => (
					<Grid item xs={12} key={object.id}>
						<ObjectCard
							mobile={true}
							list={false}
							key={object.id}
							denomination={object.denomination}
							catalogue={object.catalogue.categorie_fourmi}
							photo={
								object.photos.length > 0
									? `photos/${object.photos[0].lien}`
									: "photos/logos/kikassa-default.png"
							}
						/>
					</Grid>
				))}
			</Grid>
		);
	}
}
