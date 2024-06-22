import Navbar from '../components/Navbar';
import UserProfile from '../components/UserProfile';
import ProfileSettings from '../components/ProfileSettings';

function UserProfilePage() {
  return (
    <div>
      <Navbar item="profile"></Navbar>
      <UserProfile></UserProfile>

      <ProfileSettings></ProfileSettings>
    </div>
  );
}

export default UserProfilePage;
