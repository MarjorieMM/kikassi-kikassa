import React, { useState } from "react";
import { AppBar, Tabs, Tab, Box, Typography } from "@material-ui/core";
import { makeStyles } from "@material-ui/core/styles";
import useFetch from "../utils/useFetch";
import { ObjectsList } from "./ObjectsList";

const useStyles = makeStyles((theme) => ({
	indicator: {
		height: "2px",
		backgroundColor: "#fff",
	},
	bar: {
		backgroundColor: theme.palette.primary.dark,
	},
}));

function TabPanel({ children, value, index, ...other }) {
	return (
		<div role="tabpanel" hidden={value !== index} {...other}>
			{value === index && <Box p={3}>{children}</Box>}
		</div>
	);
}

function ObjectsTabs(val) {
	const classes = useStyles();
	const [currentTab, setCurrentTab] = useState(1);
	const { data: sscategories } = useFetch("/api/sous_categories");
	const { data: categories } = useFetch("/api/categories");

	// const objet = val.val.map((obj) => obj.sous_categorie.nom_ss_categorie);
	// const sscat = categories.map((cat) => cat.sousCategories);
	// const sas = sscat.map((ss) => ss.map((s) => s.nom_ss_categorie));
	// console.log("object", objet, "sas");

	// const sscats = sscategories.map((ss) => ss.nom_ss_categorie);
	// const cats = categories.map((cat) => cat.nom_categorie);
	// console.log(categories.map((cat) => cat));
	// console.log(objects.map((ob) => ob));
	// console.log(
	// 	objects.map((obj) => ({
	// 		nom: obj.denomination,
	// 		sscat: obj.sous_categorie.nom_ss_categorie,
	// 	}))
	// );

	const handleChange = (event, newTab) => {
		setCurrentTab(newTab);
	};
	return (
		<>
			<AppBar position="static" className={classes.bar}>
				<Tabs
					classes={{ indicator: classes.indicator }}
					value={currentTab}
					indicatorColor="secondary"
					onChange={handleChange}
					variant="scrollable"
					scrollButtons="auto"
					aria-label="categories"
				>
					{categories.map((cat) => (
						<Tab key={cat.id} label={cat.nom_categorie} />
					))}
				</Tabs>
			</AppBar>
			{categories.map((cat, index) => (
				<TabPanel key={cat.id} value={currentTab} index={index}>
					{/* {cat.sousCategories.map((ss) => (
						<li>{ss.nom_ss_categorie}</li>
					))}
					<br />  */}
					{/* {val.val.map((obj) => (
						<li>{obj.denomination}</li>
					))} */}
					<ObjectsList />
				</TabPanel>
			))}
		</>
	);
}

export default ObjectsTabs;
