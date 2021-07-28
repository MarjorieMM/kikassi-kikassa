import React from "react";
import Grid from "@material-ui/core/Grid";
import useFetch from "../utils/useFetch";
import ObjectCard from "./ObjectCard";
import { makeStyles, styled } from "@material-ui/core/styles";

const useStyles = makeStyles((theme) => ({
	marginAuto: {
		margin: "auto",
	},
}));

export function ObjectsList() {
	const { data: objects, error, isLoaded } = useFetch("/api/objets");
	const { data: categories } = useFetch("/api/categories");
	const classes = useStyles();

	// const sscat = categories.map((cat) =>
	// 	cat.sousCategories.map((c) => c.nom_ss_categorie)
	// );

	// console.log(objects.map((obj) => obj.vitrine));

	if (error) {
		return <div>Erreur: {error}</div>;
	} else if (!isLoaded) {
		return <div>Chargement...</div>;
	} else {
		return (
			<Grid container>
				{objects.map((object) => (
					<Grid item xs={12} md={6} lg={4} key={object.id}>
						<ObjectCard
							list={true}
							denomination={object.denomination}
							description={object.description}
							categories={categories}
							catalogue={object.catalogue.categorie_fourmi}
							photo={
								object.photos.length > 0
									? `photos/${object.photos[0].lien}`
									: "photos/logos/kikassa-default.png"
							}
							// souscategories={categories.sousCategories}
						/>
					</Grid>
				))}
			</Grid>
		);
	}
}
