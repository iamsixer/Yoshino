package yoshino.repositories;

import org.springframework.data.jpa.repository.JpaRepository;
import yoshino.models.Channel;
import yoshino.models.User;

/**
 * Created by Volio on 2017/1/7.
 */
public interface ChannelRepository extends JpaRepository<Channel, Long> {

    Channel findOneByUser(User user);

    Channel findOneByStreamKey(String streamKey);
}
