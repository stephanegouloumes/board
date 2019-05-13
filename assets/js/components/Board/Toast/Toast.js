import React, { Component } from 'react'

class Toast extends Component {
    constructor(props) {
        super(props)

        this.state = {
            message: ''
        }
    }

    componentWillReceiveProps(props) {
        if (props.message !== '') {
            this.setState({ message: props.message }, this.clearMessage())
        }
    }

    clearMessage = () => {
        setTimeout(() => {
            this.setState({ message: '' })
        }, 3000)
    }

    hide = () => {
        this.setState({ message: '' })
    }

    render() {
        return (
            <div className={this.state.message ? 'o-toast is-shown' : 'o-toast'} onClick={this.hide}>
                <p>{this.state.message}</p>
            </div>
        )
    }
}

export default Toast
