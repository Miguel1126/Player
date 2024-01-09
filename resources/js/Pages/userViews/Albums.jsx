import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";
import TextInput from "@/Components/TextInput";
import { useRef, useState, useEffect } from "react";
import { useForm } from "@inertiajs/react";
import { Head } from "@inertiajs/react";
import DangerButton from "@/Components/DangerButton";
import PrimaryButton from "@/Components/PrimaryButton";
import WarningButton from "@/Components/WarningButton";
import Swal from "sweetalert2";
import axios from "axios";


export default function Dashboard(props) {
    const [modal, setModal] = useState(false);
    const [title, setTitle] = useState("");
    const [operation, setOperation] = useState(1);
    const TitleInput = useRef();
    const ImgInput = useRef();
    const {
        data,
        setData,
        delete: destroy,
        post,
        put,
        processing,
        reset,
        errors,
    } = useForm({
        id: "",
        title: "",
        img: "",
    });

    const openModal = (op, id, title, img) => {
        setModal(true);
        setOperation(op);

        if (op === 1) {
            setTitle("Añadir álbum");
            setData({ title: "", img: "" }); 
        } else {
            setTitle("Modificar álbum");
            setData({ id: id, title: title, img: img });
        }
    };

    const [previewImage, setPreviewImage] = useState("");

    useEffect(() => {
        console.log("Componente desmontado");
        setPreviewImage("");
        setData({ title: "", img: "" });
    }, []);

    const handleImageChange = (e) => {
        const file = e.target.files[0];
    
        if (file && file.type.startsWith("image/")) {
            // Mostrar una vista previa de la imagen seleccionada
            const reader = new FileReader();
            reader.onloadend = () => {
                setPreviewImage(reader.result);
            };
            reader.readAsDataURL(file);
    
            // Actualizar el estado con la información de la imagen seleccionada
            setData((prevData) => ({
                ...prevData,
                img: file,
            }));
        } else {
            // Restablecer la vista previa y el estado si el archivo no es una imagen
            setPreviewImage("");
            setData((prevData) => ({
                ...prevData,
                img: null,
            }));
        }
    };

    const closeModal = () => {
        setModal(false);
    };

    const save = async (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append("title", data.title);
        formData.append("img", data.img);
    
        try {
            if (operation === 1) {
                await axios.post(route("albums.store"), formData, { timeout: 30000 });
                closeModal();
                Swal.fire("Álbum agregado correctamente", "", "success");
            } else {
                const response = await axios.post(route("updateAlbum", { album: data.id }), formData);
                
                // Actualizar el estado después de una respuesta exitosa
                setData((prevData) => ({
                    ...prevData,
                    id: response.data.id,
                }));
    
                closeModal();
                Swal.fire("Álbum modificado correctamente", "", "success");
                console.log(data)
                console.log(response)
            }
        } catch (error) {
            console.error("Error al enviar el formulario:", error);
        }
    };

    const remove = (id) => {
        console.log("Eliminar álbum con ID:", id);
        // Pregunta al usuario para confirmar la eliminación
        Swal.fire({
          title: "¿Estás seguro?",
          text: "No podrás revertir esto.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Sí, eliminarlo",
        }).then((result) => {
          if (result.isConfirmed) {
            // Realiza la solicitud de eliminación
            axios
              .delete(route("albums.destroy", { album: id }))
              .then(() => {
                // Maneja el éxito de la eliminación
                closeModal();
                Swal.fire("Álbum eliminado correctamente", "", "success");
              })
              .catch((error) => {
                // Maneja cualquier error en la solicitud de eliminación
                console.error("Error al eliminar el álbum:", error);
              });
          }
        });
      };

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Albumes
                </h2>
            }
        >
            <Head title="Albumes" />

            <div className="bg-white grid v-screen place-items-center">
                <div className="mt-3 mb-3 flex justify-end">
                    <PrimaryButton onClick={() => openModal(1)}>
                        <i className="fa-solid fa-plus-circle"></i>
                        Agregar
                    </PrimaryButton>
                </div>
            </div>
            <div className="bg-white grid v-screen place-items-center py-6">
                <table className="table-auto border border-gray-400">
                    <thead>
                        <tr className="bg-gray-100">
                            <th className="px-2 py-2">#</th>
                            <th className="px-2 py-2">Imagen</th>
                            <th className="px-2 py-2">Titulo</th>
                            <th className="px-2 py-2">Año</th>
                            <th className="px-2 py-2">Artista</th>
                        </tr>
                    </thead>
                    <tbody>
                        {props.albums.map((album, i) => (
                            <tr key={album.id}>
                                <td className="border border-gray-400 px-2 py-2">
                                    {i + 1}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    {/* URL completa de la imagen */}
                                    <img
                                        src={`/images/users/${album.img}`}
                                        alt={album.title} 
                                        className="w-16 h-16"
                                    />
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    {album.title}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    {album.year}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                {album.user.name}
                                </td>
                                <td className="border border-gray-400 px-2 py-2">
                                    <WarningButton
                                        onClick={() =>
                                            openModal(
                                                2,
                                                album.id,
                                                album.title,
                                                album.img
                                            )
                                        }
                                    >
                                        <i className="fa-solid fa-edit"></i>
                                    </WarningButton>
                                </td>
                                <td className="border border-gray-400 px-2 py-2" >
                                    <DangerButton  onClick={() => remove(album.id)}>
                                        <i className="fa-solid fa-trash"></i>
                                    </DangerButton>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
            <Modal show={modal} onClose={closeModal}>
                <h2 className="p-3 text-lg font-medium text-gray-900">
                    {title}
                </h2>
                <form
                    onSubmit={save}
                    encType="multipart/form-data"
                    className="p-6"
                >
                    <div className="mt-6">
                        <label htmlFor="title">Titulo</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value={data.title}
                            onChange={(e) =>
                                setData({ ...data, title: e.target.value })
                            }
                            required
                            className="mt-1 block w-3/4"
                        />
                    </div>
                    <div className="mt-6">
                        <InputLabel htmlFor="img" value="Imagen"></InputLabel>
                        <input
                            type="file"
                            id="img"
                            name="img"
                            onChange={handleImageChange}
                            accept="image/*"
                            className="mt-1 block w-3/4"
                            required
                        />
                        {previewImage && (
                            <img
                                src={previewImage}
                                alt="Preview"
                                className="mt-2 w-32 h-32"
                            />
                        )}
                    </div>
                    <div className="mt-6 flex justify-end">
                        <PrimaryButton className="m-2" onClick={save}>
                            Guardar
                        </PrimaryButton>
                        <SecondaryButton className="m-2" onClick={closeModal}>
                            Cancelar
                        </SecondaryButton>
                    </div>
                </form>
            </Modal>
        </AuthenticatedLayout>
    );
}
