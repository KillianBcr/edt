import React from 'react';
import {Link, Route, Router} from 'wouter';
import Semesterlist from "../components/SemesterList";
import Grouplist from "../components/GroupList";
import Semester from "../components/SemesterDetail";
import  Me from "../components/Me";
import  Repartition from "../components/Repartition";
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Group from "../components/GroupDetail";
import SemesterDetail from "../components/SemesterDetail";
import Graph from "../components/Graph";

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
                    <Semesterlist/>
                    <Semester/>
                    <ToastContainer/>
                </Route>
            </Router>
        </div>
    );
}

export default App;
