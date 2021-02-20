import { useState, forwardRef, useImperativeHandle } from "react";
import * as apiService from '../service/ApiService';

const TextField = forwardRef((props, ref) => {

    const [value, setValue] = useState("");
    const [message, setMessage] = useState("");
    const [messageClass, setMessageClass] = useState("fadeOut");

    const messages = {
        correct : "Good job - Correct 🎉!!!",
        empty : "You have to type in a number 🥴",
        notNumber : "That is not a number 😊. Write a number.",
        wrong : "Not correct. Try again 💌 "
    }

    useImperativeHandle(ref, () => ({
        reset : () => {
            setMessage("");
            setMessageClass("fadeOut");
            setValue("");
        }
    }), []);

    const onChange = (event) => {
        setMessageClass('fadeIn fadeOut');

        const inputVal = event.target.value;
        setValue(inputVal);
        
    } 

    const handleSubmit = () => {
        if (value.trim() == "") {
            setMessage(messages.empty);
            setMessageClass('fadeIn');
            return;
        }

        if (!isNumeric(value.trim())) {
            setMessage(messages.notNumber)
            setMessageClass('fadeIn');
            return;
        }

        apiService.post(props.expression, value).then(response => {
            setMessageClass('fadeIn');
            console.log(response);

            if (response.data.status) {
                setMessage(messages.correct)
                props.onSuccess && props.onSuccess();
            } else {
                setMessage(messages.wrong)
            }
            
        });
    }

    const isNumeric = (str) => {
        if (typeof str != "string") return false    // we only process strings!  
        return !isNaN(str) &&                       // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
                !isNaN(parseFloat(str))             // ...and ensure strings of whitespace fail
    }

    return (
        <div className="TextField">
            <div className="labelWrapper w20">
                <span className="label">Solution: </span>
            </div>
            <div className="w80 fieldWrapper">
                <input onChange={onChange} value={value}></input>
                <div className="underline"></div>
            </div>
            <div className="rowSubmit">
                <div id="buttonSubmit" className="button" onClick={handleSubmit}>
                    Submit
                </div>
                <p className={`errorLabel ${messageClass}`}>{message}</p>
            </div>
        </div>
    )
});
export default TextField;
