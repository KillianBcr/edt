import './bootstrap';
import { registerReactControllerComponents } from '@symfony/ux-react';
import './js/color-mode';
import './styles/app.scss';

registerReactControllerComponents(require.context('./react/controllers', true, /\.(j|t)sx?$/));
