import { registerReactControllerComponents } from '@symfony/ux-react';
import './js/color-mode';
import './styles/app.scss';
import './bootstrap';
import 'bootstrap';


registerReactControllerComponents(require.context('./react/controllers', true, /\.(j|t)sx?$/));
