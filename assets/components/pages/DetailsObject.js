import React from "react";
import Box from "@material-ui/core/Box";
import useFetch from "../utils/useFetch";
import Details from "../partials/Details";
import ObjetsContext from "../../context/ObjetsContext";

function DetailsObject(props) {
	const { data: objects, error, isLoaded } = useFetch("/api/objets");
	const { slug } = props.match.params;
	const object = objects.filter((ob) => ob.slug === slug);
	const res = object.map((obj) => obj.photos.map((photo) => photo.lien));
	console.log(res);

	return (
		<Box mt={15}>
			<Box p={3}>
				{object.map((obj) => (
					<Details
						key={obj.id}
						slug={obj.slug}
						name={obj.denomination}
						photos={obj.photos}
					/>
				))}
			</Box>
		</Box>
	);
}

export default DetailsObject;
