import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import ReactRefresh from "@vitejs/plugin-react-refresh";

import { VitePWA } from "vite-plugin-pwa";
import WindiCSS from "vite-plugin-windicss";
// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react(), ReactRefresh(), VitePWA(), WindiCSS()],
});
