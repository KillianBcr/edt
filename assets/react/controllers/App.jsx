import React from 'react';
import {Link, Route, Router} from 'wouter';
import Semesterlist from "../components/SemesterList";
import  Me from "../components/Me";
import  Repartition from "../components/Repartition";
import 'react-toastify/dist/ReactToastify.css';
import Graph from "../components/Graph";
import {Wish} from "../components/Wish";

function App() {
    return (
        <div className="app">
            <Router>
                <Route path="/">
                    <Me/>
                </Route>

                <Route path="/">
                    <Repartition/>
                </Route>
                <Route path="/">
                    <Graph/>
                </Route>
                <Route path="/react/semesters">
                    <Semesterlist/>
                </Route>
                <Route path="/react/semesters/:id">
                    <Wish />
                </Route>
            </Router>
        </div>
    );
}

export default App;
