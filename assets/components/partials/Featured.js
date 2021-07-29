import React from "react";
import useFetch from "../utils/useFetch";
import ObjectCard from "./ObjectCard";
import Carousel from "../utils/Carousel";

export default function Featured() {
	const { data: objects, error, isLoaded } = useFetch("/api/objets");
	const { data: categories } = useFetch("/api/categories");

	const vitrine = [];
	objects.map((obj) => obj.vitrine && vitrine.push(obj));

	if (error) {
		return <div>Erreur: {error.message}</div>;
	} else if (!isLoaded) {
		return <div>Chargement...</div>;
	} else {
		return (
			<div>
				<Carousel infiniteLoop={true}>
					{vitrine.map((object) => (
						<ObjectCard
							list={false}
							key={object.id}
							denomination={object.denomination}
							categories={categories}
							catalogue={object.catalogue.categorie_fourmi}
							photo={
								object.photos.length > 0
									? `photos/${object.photos[0].lien}`
									: "photos/logos/kikassa-default.png"
							}
						/>
					))}
				</Carousel>
			</div>
		);
	}
}
