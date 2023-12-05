import React, { useState, useEffect } from 'react';
import Semesterlist from "./SemesterList";
import Semester from "./SemesterDetail";
import {ToastContainer} from "react-toastify";

export function Wish() {
    const [selectedTags, setSelectedTags] = useState([]);
    console.log(selectedTags);
    return (
        <div>
            <Semesterlist changeSelectedTag={setSelectedTags} />
            <Semester selectedTags={selectedTags} />
            <ToastContainer/>
        </div>
    )
}