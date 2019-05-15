import React, { Component } from 'react'

class Toast extends Component {
    constructor(props) {
        super(props)

        this.state = {
            message: ''
        }
    }

    componentWillReceiveProps(props) {
        if (props.toast.message !== '') {
            this.setState({ message: props.toast.message }, this.clearMessage())
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

    cssClasses = () => {
        let classes = 'o-toast'

        if (this.state.message) {
            classes += ' is-shown'
        }

        if (this.props.toast.type === 'error') {
            classes += ' is-danger'
        }

        return classes
    }

    render() {
        return (
            <div className={this.cssClasses()} onClick={this.hide}>
                <p>{this.state.message}</p>
            </div>
        )
    }
}

export default Toast
